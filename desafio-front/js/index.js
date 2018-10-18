'use strict';
// https://www.w3schools.com/js/js_strict.asp

const url = 'http://localhost/desafio/v1/product';
const DONE = 4;
const OK = 200;
const SERVER_ERROR = 500;
/// vai chamar a função assim que o documento terminar de carregar

var products = [];
function addProduct(p) {
  products.push(p);
}

var list = document.querySelector('#list');

function recuperarTodos() {
  list.innerHTML = '';
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url);
  xhr.send(null);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === DONE) {
      if (xhr.status === OK) {
        var response = JSON.parse(xhr.response);
        response.products.forEach(element => {
          var p = document.createElement('div');
          p.classList = 'product'
          p.innerHTML = `
          <span class="name">${element.name}</span>
          <span class="price">${element.price}</span>
          <button onClick="editar(this)"  class="button" product-id="${element.id}">Editar</button>
          <button onClick="excluir(this)" class="button" product-id="${element.id}">Excluir</button>`;
          list.appendChild(p);
        });
      } else {
        alert('Erro ao recuperar os dados da API');
        console.log('Error: ' + xhr.status);
      }
    }
  }
}

// usando IIFE para executar a função logo ao carregar a página
// https://stackoverflow.com/questions/8228281/what-is-the-function-construct-in-javascript
(function () {
  recuperarTodos();
})();


function editar(element) {
  window.location.href = 'form.html?id=' + element.getAttribute('product-id');
}

function excluir(element) {
  var confirmation = confirm('Vocë realmente quer excluir?');
  if (confirmation) {
    var xhr = new XMLHttpRequest();
    xhr.open('DELETE', url + '/' + element.getAttribute('product-id'));
    xhr.send(null);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === DONE) {
        if (xhr.status === OK) {
          recuperarTodos();
        } else {
          alert('Erro ao excluir os dados da API');
          console.log('Error: ' + xhr.status);
        }
      }
    }
  }
}