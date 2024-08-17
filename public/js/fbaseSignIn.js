import {
  getAuth,
  GoogleAuthProvider,
  signInWithEmailAndPassword,
  signInWithPopup,
  onAuthStateChanged,
} from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();
const provider = new GoogleAuthProvider();

const signInGoogleButton = document.getElementById("google-signin");

const userGoogleSignIn = async () => {
  injectBarLoader('bar-loader');

  signInWithPopup(auth, provider)
    .then((result) => {
      const user = result.user;
      // Handle sign-in success
    removeBarLoader('bar-loader');

    })
    .catch((error) => {
      // Handle sign-in error
    removeBarLoader('bar-loader');

      console.error("Google sign-in error:", error);
    });
};

// Form submission handler
document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission
    injectBarLoader('bar-loader');
    const verificationElement = document.getElementById("verification");
    if (verificationElement) {
      verificationElement.style.display = "none";
    }

    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    signInWithEmailAndPassword(auth, email, password)
      .then(function (userCredential) {
        removeBarLoader('bar-loader');
        var errorMessage = document.getElementById("error-message");
        if (errorMessage) {
          errorMessage.style.display = "none";
        }
      })
      .catch(function (error){
        removeBarLoader('bar-loader');
        // Show error message
        var errorMessage = document.getElementById("error-message");
        if (errorMessage) {
          errorMessage.style.display = "block";
          errorMessage.textContent = "Email or Password is invalid";
        } else {
          console.error("Error message element not found");
        }
      });
  });

  const maxRetries = 5;
  const delay = 500; // Delay in milliseconds
  
  function fetchWithRetry(url, retries) {
    injectBarLoader('bar-loader'); // Show loader
    
    return fetch(url, { method: "GET" })
      .then((result) => {
        if (result.status === 200) {
          window.location = result.redirect;
        } else {
          throw new Error(result.statusText);
        }
      })
      .catch((error) => {
        if (retries > 0) {
          // console.warn(`Retrying in ${delay / 1000} seconds... (${maxRetries - retries + 1}/${maxRetries})`);
          return new Promise((resolve) =>
            setTimeout(() => resolve(fetchWithRetry(url, retries - 1)), delay)
          );
        } else {
          console.error("Auth process fetch error:", error);
        }
      })
      .finally(() => {
        removeBarLoader('bar-loader'); // Hide loader after fetch
      });
  }
  
  onAuthStateChanged(auth, (user) => {
    if (user) {
      let url =
        baseUrl + "auth-process?fr=" + user.accessToken + "&br=" + user.uid;
        // console.log(url);
      fetchWithRetry(url, maxRetries);
    }
  });
  
if (signInGoogleButton) {
  signInGoogleButton.addEventListener("click", userGoogleSignIn);
} else {
  console.error("Google sign-in button element not found");
}
