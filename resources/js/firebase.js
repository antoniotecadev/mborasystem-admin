import { initializeApp } from "firebase/app";
import { getDatabase } from "firebase/database";

const firebaseConfig = {
    apiKey: "AIzaSyCRevSFiqHU5TfNNgGHc2bgAPi9MseAxaM",
    authDomain: "mborasystem-admin.firebaseapp.com",
    databaseURL: "https://mborasystem-admin-default-rtdb.firebaseio.com",
    projectId: "mborasystem-admin",
    storageBucket: "mborasystem-admin.appspot.com",
    messagingSenderId: "1024278380960",
    appId: "1:1024278380960:web:6ad069d4010a69662c24c5",
    measurementId: "G-ZW05HBY5YG"
};
// Initialize Firebase
const app = initializeApp(firebaseConfig);
// Get a reference to the database service
const database = getDatabase(app);
export default database;