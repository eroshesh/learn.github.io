  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
  import { getDatabase, ref, set, get, child } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";


  const firebaseConfig = {
    apiKey: "AIzaSyDRJBQw6qAAUet-taY2TX2HUFBrxRIpf6I",
    authDomain: "login-e61f8.firebaseapp.com",
    databaseURL: "https://login-e61f8-default-rtdb.firebaseio.com",
    projectId: "login-e61f8",
    storageBucket: "login-e61f8.appspot.com",
    messagingSenderId: "1089768534152",
    appId: "1:1089768534152:web:dcea2782ccc00e045d2f3d"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  
  //ref para sa database shesh services
  const db = getDatabase(app);

  document.getElementById("submit").addEventListener('click', function(e){
    set(ref(db, 'user/' + document.getElementById("username").value),
    {

      username: document.getElementById("username").value,
      password: document.getElementById("password").value

    });
    alert("login sucessfull")
  })