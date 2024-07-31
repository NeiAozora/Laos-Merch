import { getAuth, GoogleAuthProvider, signInWithEmailAndPassword, signInWithPopup, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();
const provider = new GoogleAuthProvider();

const signInGoogleButton = document.getElementById("google-signin");

const userGoogleSignIn = async () => {
    signInWithPopup(auth, provider)
    .then((result) => {
        const user = result.user;

    }).catch((error) => {

    });
}



// Form submission handler
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    document.getElementById('verification').style.display = 'none';
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    signInWithEmailAndPassword(auth, email, password)
        .then(function(userCredential) {
            var errorMessage = document.getElementById('error-message');
            errorMessage.style.display = 'none';
        })
        .catch(function(error) {
            // Show error message
            var errorMessage = document.getElementById('error-message');
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'Email or Password is invalid';
        });
});

onAuthStateChanged(auth, (user) => {
    if (user) {
        let url = baseUrl + "auth-process?fr=" + user.accessToken + "&br=" + user.uid;
        // window.location = url;
        const response = fetch(url, {
            method: 'GET'
        }).then((result) => {
        if(result.status == 200){
            window.location = baseUrl;
        } else {
            console.log(error);
        }
    }).catch((error) => {
        console.log(error);
    });

    }
});

signInGoogleButton.addEventListener("click", userGoogleSignIn);
