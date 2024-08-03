import { getAuth, signOut } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const auth = getAuth();

signOut(auth).then(() => {
    const currentUrl = window.location.href;
    const redirectUrl =  baseUrl + "logout?to=" + encodeURIComponent(currentUrl);
    console.log(redirectUrl);
    // window.location.href = redirectUrl;
}).catch((error) => {
    console.log(error);
});
