const excerpt = document.querySelectorAll('.panel-news_article');

[...excerpt].map((item) => {
  item.addEventListener(
    'click',
    function(event) {
      if (event.target.classList.contains('summary-link')) {
        event.preventDefault();
        item.querySelector('.summary').classList.toggle('display-none');
        if (item.querySelector('.summary').classList.contains('display-none')) {
          setTimeout(function() {item.querySelector('.summary').style = 'display:none';}, 500);
        } else {
          item.querySelector('.summary').style = '';
          setTimeout(function() {item.querySelector('.summary').style = 'display:block';}, 500);
        }
      }
    },
    false
  );
});