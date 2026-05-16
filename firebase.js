// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyADX4vazskz2sHYo758pvxvc_wjl2Pfqu8",
  authDomain: "mindly-848ae.firebaseapp.com",
  projectId: "mindly-848ae",
  storageBucket: "mindly-848ae.firebasestorage.app",
  messagingSenderId: "266291731505",
  appId: "1:266291731505:web:395b678bb79908fa700910",
  measurementId: "G-KS2XEKKRN0"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
