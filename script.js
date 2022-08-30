const toggle = document.querySelector(".toggle"),
input = document.getElementById('password');

toggle.addEventListener("click", () =>{
    if(input.type === "password"){
        // console.log('yah');
        input.type = "text";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
    }
    else{
        // console.log('nope');
        input.type = "password";
        toggle.classList.replace("fa-eye", "fa-eye-slash");
    }
})