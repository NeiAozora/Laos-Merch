import { getAuth, signOut } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();

signOut(auth).then(() => {
    const currentUrl = window.location.href;
    const redirectUrl = "/logout?to=" + encodeURIComponent(currentUrl);
    window.location.href = redirectUrl;
}).catch((error) => {
    console.log(error);
});
