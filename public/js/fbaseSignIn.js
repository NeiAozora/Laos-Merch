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
  signInWithPopup(auth, provider)
    .then((result) => {
      const user = result.user;
      // Handle sign-in success
    })
    .catch((error) => {
      // Handle sign-in error
      console.error("Google sign-in error:", error);
    });
};

// Form submission handler
document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission
    const verificationElement = document.getElementById("verification");
    if (verificationElement) {
      verificationElement.style.display = "none";
    }

    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    signInWithEmailAndPassword(auth, email, password)
      .then(function (userCredential) {
        var errorMessage = document.getElementById("error-message");
        if (errorMessage) {
          errorMessage.style.display = "none";
        }
      })
      .catch(function (error) {
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

onAuthStateChanged(auth, (user) => {
  if (user) {
    let url =
      baseUrl + "auth-process?fr=" + user.accessToken + "&br=" + user.uid;
    fetch(url, {
      method: "GET",
    })
      .then((result) => {
        if (result.status === 200) {
          window.location = baseUrl;
        } else {
          console.error("Auth process error:", result.statusText);
        }
      })
      .catch((error) => {
        console.error("Auth process fetch error:", error);
      });
  }
});

if (signInGoogleButton) {
  signInGoogleButton.addEventListener("click", userGoogleSignIn);
} else {
  console.error("Google sign-in button element not found");
}
