"use strict";


document.addEventListener('DOMContentLoaded', function(){
    document.addEventListener('click', function(event){
        if(event.target.closest('.db-pos-cartBtn')){
            document.querySelectorAll('.db-pos-cartDiv').forEach(function(obj){
                obj.classList.toggle('active');
            })
        }
        if(event.target.closest('.db-pos-cartCls')){
            document.querySelectorAll('.db-pos-cartDiv').forEach(function(obj){
                    obj.classList.remove('active');
            })
        }
    })
});


/* POS responsive close */

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.db-sidebar-nav-menu').forEach(function(obj) {
        if (obj.classList.contains('router-link-exact-active')) {
            obj.parentElement.classList.add('active');
        }
    });
    document.addEventListener('click', function(event) {
        if (event.target.closest('.db-sidebar-nav-menu')) {
            document.querySelectorAll('.db-sidebar-nav-menu').forEach(function(obj) {
                if (obj.parentElement.classList.contains('active')) {
                    obj.parentElement.classList.remove('active');
                }
            });
            event.target.closest('.db-sidebar-nav-menu').parentElement.classList.add('active');
        }
    });
    document.addEventListener('click', function(event) {
        if (event.target.closest('.db-breadcrumb-item')) {
            document.querySelectorAll('.db-sidebar-nav-menu').forEach(function(obj) {
                if (obj.parentElement.classList.contains('active')) {
                    obj.parentElement.classList.remove('active');
                }
            });
            document.querySelectorAll('.db-sidebar-nav-menu').forEach(function(obj) {
                if (obj.classList.contains('router-link-exact-active')) {
                    obj.parentElement.classList.add('active');
                }
            });
        }
    });
});



/* Active side bar menu end */

const handleLinkForInstaller = (param) => {
    window.location.replace(param);
}

document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener('click', function(event) {
        if (event.target.closest('.close-alert-button')) {
            const button = event.target.closest('.close-alert-button');
            button.parentElement.remove();
        }
    });
});
/* Installer menu active code close */


/* Setting menu code start */

document.addEventListener("DOMContentLoaded", function() {
    let toggleValue = false;

    document.addEventListener('click', function(event) {
        if (event.target.closest('.settings-btn')) {
            toggleValue = !toggleValue;
            const button = event.target.closest('.settings-btn');
            const siblings = Array.from(button.parentNode.children).filter(child => child !== button);
            const siblingElement = siblings[0];
            const pixel = siblingElement.scrollHeight;
            
            if (toggleValue) {
                siblingElement.style.height = `${pixel}px`;
                button.querySelector('.fa-solid').classList.remove('fa-chevron-down');
                button.querySelector('.fa-solid').classList.add('fa-chevron-up');
            } else {
                siblingElement.style.height = `0px`;
                button.querySelector('.fa-solid').classList.remove('fa-chevron-up');
                button.querySelector('.fa-solid').classList.add('fa-chevron-down');
            }
        }
    });
});

/* Setting menu code end */
