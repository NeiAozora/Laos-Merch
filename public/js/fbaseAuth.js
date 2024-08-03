import {
  getAuth,
  GoogleAuthProvider,
  signInWithPopup,
  onAuthStateChanged,
  signOut,
} from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();

const maxRetries = 5;
const delay = 500; // Delay in milliseconds

function fetchWithRetry(url, retries) {
  return fetch(url, { method: "GET" })
    .then((result) => {
      if (result.status === 200) {
        // Optionally handle a successful response if needed
      } else if (result.status === 500) {
        // Handle server error
        console.error("Server error occurred. Status: 500");
      } else {
        // Handle other statuses
        throw new Error("Unhandled response status: " + result.status);
      }
    })
    .catch((error) => {
      if (retries > 0) {
        // console.warn(`Retrying in ${delay / 1000} seconds... (${maxRetries - retries + 1}/${maxRetries})`);
        return new Promise((resolve) =>
          setTimeout(() => resolve(fetchWithRetry(url, retries - 1)), delay)
        );
      } else {
        // Log a generic message to avoid exposing sensitive info
        console.error("Fetch request failed after retries. Error:", error.message);

        // Perform sign-out if retries are exhausted
        signOut(auth)
          .then(() => {
            const currentUrl = window.location.href;
            const redirectUrl =
              baseUrl + "Laos-Merch/logout?to=" + encodeURIComponent(currentUrl);
            window.location.href = redirectUrl;
          })
          .catch((signOutError) => {
            // Log the sign-out error with a generic message
            console.error("Sign out error:", signOutError.message);
          });
      }
    });
}

auth.onIdTokenChanged(function (user) {
  if (user) {
    user.getIdToken(true).then(function (token) {
      let url =
        baseUrl + "auth-process?fr=" + token + "&br=" + user.uid;

      fetchWithRetry(url, maxRetries);
    });
  }
});



document.addEventListener("DOMContentLoaded", function () {
  const logoutLink = document.getElementById("logout");

  if (logoutLink) {
    logoutLink.addEventListener("click", (event) => {
      event.preventDefault();

      signOut(auth)
        .then(() => {
          const currentUrl = window.location.href;
          const redirectUrl =
            baseUrl + "Laos-Merch/logout?to=" + encodeURIComponent(currentUrl);
          window.location.href = redirectUrl;
        })
        .catch((error) => {
          console.log(error);
        });
    });
  } else {
    console.log("Logout link not found");
  }
});
