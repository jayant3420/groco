let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>{
    searchForm.classList.toggle('active');
    shoppingCart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

let shoppingCart = document.querySelector('.shopping-cart');

if(document.querySelector('#cart-btn')) {
  document.querySelector('#cart-btn').onclick = () =>{
      shoppingCart.classList.toggle('active');
      searchForm.classList.remove('active');
      loginForm.classList.remove('active');
      navbar.classList.remove('active');
  }
}

let loginForm = document.querySelector('.login-form');

document.querySelector('#login-btn').onclick = () =>{
    loginForm.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    loginForm.classList.remove('active');
}

window.onscroll = () =>{
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

var swiper = new Swiper(".product-slider", {
    loop:true,
    spaceBetween: 20,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    centeredSlides: true,
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      1020: {
        slidesPerView: 3,
      },
    },
});

var swiper = new Swiper(".review-slider", {
    loop:true,
    spaceBetween: 20,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    centeredSlides: true,
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      1020: {
        slidesPerView: 3,
      },
    },
});

let nameBox = document.querySelector('#name')
let createAccount = document.querySelector('#createAccount')
let loginBtn = document.querySelector('#loginBtn')
let createNow = document.querySelector('#createNow')
let loginNow = document.querySelector('#loginNow')
let loginText = document.querySelector('#loginText')

document.querySelector('#createNow').onclick = () =>{
  nameBox.classList.remove('hidden')
  createAccount.classList.remove('hidden')
  loginBtn.classList.add('hidden')
  createNow.classList.add('hidden')
  loginNow.classList.remove('hidden')
  loginText.innerHTML = 'Create Account'
}

document.querySelector('#loginNow').onclick = () => {
  nameBox.classList.add('hidden')
  createAccount.classList.add('hidden')
  loginBtn.classList.remove('hidden')
  createNow.classList.remove('hidden')
  loginNow.classList.add('hidden')
  loginText.innerHTML = 'Login Now'
}