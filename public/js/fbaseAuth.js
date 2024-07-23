import { getAuth, GoogleAuthProvider, signInWithPopup, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();
const provider = new GoogleAuthProvider();

document.addEventListener("DOMContentLoaded", function() {
    const logoutLink = document.getElementById("logout");
    
    if (logoutLink) {
        logoutLink.addEventListener("click", (event) => {
            event.preventDefault(); 
            
            signOut(auth).then(() => {
                const currentUrl = window.location.href;
                const redirectUrl = "/logout?to=" + encodeURIComponent(currentUrl);
                window.location.href = redirectUrl;
            }).catch((error) => {
                console.log(error);
            });
        });
    } else {
        console.log("Logout link not found");
    }
});