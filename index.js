const excerpt = document.querySelectorAll('.panel-news_article');

[...excerpt].map((item) => {
  item.addEventListener(
    'click',
    function(event) {
      if (event.target.classList.contains('summary-link')) {
        event.preventDefault();
        item.querySelector('.summary').classList.toggle('display-none');
      }
    },
    false
  );
});