if(document.querySelector("#oeil")){

    document.querySelector('#oeil').addEventListener('click',function(){

        let mdp = document.querySelector('#mdp');
        let icone = document.querySelector('#oeil i');

        if(mdp.getAttribute('type') == 'password'){
            mdp.setAttribute('type','text');
            icone.classList.replace('fa-eye','fa-eye-slash');
        }
        else{
            mdp.setAttribute('type','password');
            icone.classList.replace('fa-eye-slash','fa-eye');
        }
    });

}


document.addEventListener("DOMContentLoaded", function() {
    let modal = document.getElementById("confirm-delete");
    let btnOk = modal.querySelector(".btn-ok");
    let deleteLinks = document.querySelectorAll("[data-target='#confirm-delete']");

    deleteLinks.forEach(function(deleteLink) {
      deleteLink.addEventListener("click", function(event) {
        let href = deleteLink.getAttribute("data-href");
        btnOk.setAttribute("href", href); // Utilisez "href" au lieu de "data-href"
      });
    });

    btnOk.addEventListener("click", function() {
      let href = btnOk.getAttribute("href"); // Utilisez "href" au lieu de "data-href"
      if (href) {
        window.location.href = href;
      }
    });
  });


window.addEventListener('DOMContentLoaded', () => {
    const photoInput = document.getElementById('photo');
    const previewImg = document.getElementById('preview');
  
    photoInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
  
      if (file) {
        const reader = new FileReader();
  
        reader.addEventListener('load', (e) => {
          previewImg.src = e.target.result;
        });
  
        reader.readAsDataURL(file);
      }
    });
  });