const excerpt = document.querySelectorAll('.panel-news_article');

[...excerpt].map((item) => {
  item.addEventListener(
    'click',
    function(event) {
      if (event.target.classList.contains('news-title')) {
        event.preventDefault();

        const summary = item.querySelector('.summary');
        summary.classList.toggle('display-none');
        if (summary.classList.contains('display-none')) {
          setTimeout(function() {summary.style = 'display:none';}, 500);
        } else {
          summary.style = 'display:block';
        }
      }
    },
    false
  );
});