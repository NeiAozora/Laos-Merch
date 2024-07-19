import { getAuth, GoogleAuthProvider, signInWithPopup, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();
const provider = new GoogleAuthProvider();

const signInGoogleButton = document.getElementById("google-signin");

const userGoogleSignIn = async () => {
    signInWithPopup(auth, provider)
    .then((result) => {
        const user = result.user;
        console.log(user);
    }).catch((error) => {
        console.log(error);
    });
}

onAuthStateChanged(auth, (user) => {
    if (user) {
        const response = fetch(baseUrl + "auth-process?fr=" + user.accessToken + "&br=" + user.uid, {
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
