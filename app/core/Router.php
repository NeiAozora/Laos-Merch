<?php
/**
 * Kelas Router
 *
 * Mengelola routing dan middleware untuk permintaan HTTP dalam aplikasi web.
 * Kelas ini mengimplementasikan pola singleton untuk memastikan bahwa hanya 
 * satu instance yang ada selama siklus hidup aplikasi.
 *
 * Memungkinkan pendaftaran rute dengan metode HTTP tertentu (GET, POST),
 * mendukung middleware untuk penggunaan spesifik rute dan global, serta 
 * menyediakan handler khusus untuk 404 Not Found dan pengecualian.
 *
 * Contoh penggunaan:
 * 
 * Router::get('/home', function() {
 *     // Menangani permintaan GET untuk /home
 * });
 *
 * Router::notFound(function() {
 *     // Handler 404 khusus
 * });
 *
 * Router::setExceptionHandler(function($e) {
 *     // Handler pengecualian khusus
 * });
 */
class Router {
    /**
     * @var Router|null Instance singleton dari kelas Router.
     */
    private static $instance = null;

    /**
     * @var array Menyimpan rute yang terdaftar terorganisir berdasarkan metode HTTP dan path.
     */
    private $routes = [];

    /**
     * @var array Menyimpan middleware global yang diterapkan ke semua rute.
     */
    private $globalMiddlewares = [];

    /**
     * @var callable|null Menangani kesalahan 404 Not Found.
     */
    private $notFoundHandler;

    /**
     * @var callable|null Menangani pengecualian yang dilempar selama pengiriman rute.
     */
    private $exceptionHandler;

    /**
     * @var string Menyimpan metode permintaan HTTP saat ini.
     */
    private $currentMethod;

    /**
     * @var string Menyimpan path permintaan HTTP saat ini.
     */
    private $currentPath;

    /**
     * Konstruktor privat mencegah instansiasi langsung.
     */
    private function __construct() {}

    /**
     * Mengembalikan instance singleton dari kelas Router.
     *
     * @return Router Instance singleton dari kelas Router.
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * Mendaftarkan rute GET.
     *
     * @param string $path Path rute.
     * @param callable $callback Fungsi callback untuk menangani rute.
     * @param array $middlewares Opsional. Middleware yang diterapkan pada rute.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     */
    public static function get(string $path, callable $callback, array $middlewares = []): self {
        return self::getInstance()->addRoute('GET', $path, $callback, $middlewares);
    }

    /**
     * Mendaftarkan rute POST.
     *
     * @param string $path Path rute.
     * @param callable $callback Fungsi callback untuk menangani rute.
     * @param array $middlewares Opsional. Middleware yang diterapkan pada rute.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     */
    public static function post(string $path, callable $callback, array $middlewares = []): self {
        return self::getInstance()->addRoute('POST', $path, $callback, $middlewares);
    }

    /**
     * Menetapkan handler untuk kesalahan 404 Not Found.
     *
     * @param callable $callback Fungsi callback untuk menangani kesalahan 404.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     */
    public static function notFound(callable $callback): self {
        self::getInstance()->setNotFoundHandler($callback);
        return self::getInstance();
    }

    /**
     * Menetapkan handler pengecualian global.
     *
     * @param callable $callback Fungsi callback untuk menangani pengecualian.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     */
    public static function setExceptionHandler(callable $callback): self {
        self::getInstance()->exceptionHandler = $callback;
        return self::getInstance();
    }

    /**
     * Menambahkan middleware global yang diterapkan pada semua rute.
     *
     * @param callable $middleware Fungsi middleware untuk ditambahkan.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     */
    public function addGlobalMiddleware(callable $middleware): self {
        $this->globalMiddlewares[] = $middleware;
        return $this;
    }

    /**
     * Menambahkan middleware pada rute tertentu.
     *
     * @param callable $middleware Fungsi middleware untuk ditambahkan.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     */
    public function addMiddleware(callable $middleware): self {
        $this->routes[$this->currentMethod][$this->currentPath]['middlewares'][] = $middleware;
        return $this;
    }

    /**
     * Menambahkan rute ke array rute yang terdaftar.
     *
     * @param string $method Metode HTTP (GET, POST, dll.) dari rute.
     * @param string $path Path rute.
     * @param callable $callback Fungsi callback untuk menangani rute.
     * @param array $middlewares Middleware yang diterapkan pada rute.
     * @return Router Mengembalikan instance Router untuk chaining metode.
     * @throws Exception Jika rute sudah ada.
     */
    private function addRoute(string $method, string $path, callable $callback, array $middlewares): self {
        // Mencegah penulisan ulang rute yang sudah ada
        if (isset($this->routes[$method][$path])) {
            throw new Exception("Rute sudah ada untuk $method $path");
        }
        $this->routes[$method][$path] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
        return $this;
    }

    /**
     * Menetapkan handler untuk kesalahan 404 Not Found.
     *
     * @param callable $callback Fungsi callback untuk menangani kesalahan 404.
     * @return void
     */
    private function setNotFoundHandler(callable $callback): void {
        $this->notFoundHandler = $callback;
    }

    /**
     * Mengirimkan permintaan HTTP ke rute yang sesuai.
     *
     * @return void
     */
    public function dispatch(): void {
        $this->currentMethod = $_SERVER['REQUEST_METHOD'];
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->currentPath = str_replace("http://localhost/PHP-MVC-Template", "", $url);
        // $this->currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Terapkan middleware global
        foreach ($this->globalMiddlewares as $middleware) {
            call_user_func($middleware);
        }

        try {
            if (isset($this->routes[$this->currentMethod][$this->currentPath])) {
                // Terapkan middleware spesifik rute
                foreach ($this->routes[$this->currentMethod][$this->currentPath]['middlewares'] as $middleware) {
                    call_user_func($middleware);
                }
                call_user_func($this->routes[$this->currentMethod][$this->currentPath]['callback']);
            } else {
                $this->handleNotFound();
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Menangani kesalahan 404 Not Found.
     *
     * @return void
     */
    private function handleNotFound(): void {
        if ($this->notFoundHandler) {
            call_user_func($this->notFoundHandler);
        } else {
            http_response_code(404);
            echo '<h1>404 Tidak Ditemukan</h1><p>Halaman yang Anda cari tidak ada.</p>';
        }
    }

    /**
     * Menangani pengecualian yang dilempar selama pengiriman rute.
     *
     * @param Exception $e Objek pengecualian.
     * @return void
     */
    private function handleException(Exception $e): void {
        if ($this->exceptionHandler) {
            call_user_func($this->exceptionHandler, $e);
        } else {
            http_response_code(500);
            echo '<h1>500 Kesalahan Internal Server</h1><p>Terjadi kesalahan: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}
?>
