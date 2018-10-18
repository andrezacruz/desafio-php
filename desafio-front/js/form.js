'use strict';
// https://www.w3schools.com/js/js_strict.asp
const url = 'http://localhost/desafio/v1/product';
const DONE = 4;
const OK = 200;
const SERVER_ERROR = 500;

var product;
var nameInput;
var productId;
var priceInput;
var xhr;

function getProduct(id) {
  xhr = new XMLHttpRequest();
  xhr.open('GET', url + '/' + id);
  xhr.send(null);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === DONE) {
      if (xhr.status === OK) {
        product = JSON.parse(xhr.response)[0];
        productId.value = product.id;
        nameInput.value = product.name;
        priceInput.value = product.price;
      } else {
        alert('Erro ao recuperar os dados da API');
        console.log('Error: ' + xhr.status);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
      }
    }
  }
}

function cadastrar() {
  if ( nameInput.value && priceInput.value) {
    xhr = new XMLHttpRequest();
    if (productId.value) {
      xhr.open("PATCH", url + '/' + productId.value, true);
    } else {
      xhr.open("POST", url, true);
    }
  
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    xhr.send('name=' + nameInput.value + '&price=' + priceInput.value);
  
    xhr.onreadystatechange = function () {
      if (xhr.readyState === DONE) {
        if (xhr.status === OK) {
          window.location.href = 'index.html';
        } else {
          alert('Erro ao recuperar salvar os dados na API');
          console.log('Error: ' + xhr.status);
        }
      }
    }
  } else {
    alert('Todos os campos são obrigatórios!');
  }
  
}

document.addEventListener("DOMContentLoaded", function () {
  nameInput = document.querySelector('#name');
  productId = document.querySelector('#productId');
  priceInput = document.querySelector('#price');

  var id = window.location.search.substr(1);
  if (id) {
    id = id.replace('id=', '');
  }
  // EDIT 
  if (id) {
    getProduct(id);
  }
});