// Babel Compiled JS
'use strict';

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

var excerpt = document.querySelectorAll('.panel-news_article');

[].concat(_toConsumableArray(excerpt)).map(function (item) {
  item.addEventListener('click', function (event) {
    if (event.target.classList.contains('title-container')) {
      event.preventDefault();

      var summary = item.querySelector('.summary');
      summary.classList.toggle('display-none');
      if (summary.classList.contains('display-none')) {
        setTimeout(function () {
          summary.style = 'display:none';
        }, 500);
      } else {
        summary.style = 'display:block';
      }
    }
  }, false);
});

// Uncompiled JS
// const excerpt = document.querySelectorAll('.panel-news_article');

// [...excerpt].map((item) => {
//   item.addEventListener(
//     'click',
//     function(event) {
//       if (event.target.classList.contains('news-title')) {
//         event.preventDefault();

//         const summary = item.querySelector('.summary');
//         summary.classList.toggle('display-none');
//         if (summary.classList.contains('display-none')) {
//           setTimeout(function() {summary.style = 'display:none';}, 500);
//         } else {
//           summary.style = 'display:block';
//         }
//       }
//     },
//     false
//   );
// });