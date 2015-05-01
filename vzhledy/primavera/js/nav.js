function thejqueryfunction() {
  $(function(){
    $('#header nav').hide();
    $('#header #search').hide();
    
    $('#header').append('<img src="vzhledy/primavera/hamburger.png" class="toggle" id="nav-toggle">');
    if ($('#header #search').length) $('#header').append('<img src="vzhledy/primavera/search.png" class="toggle" id="search-toggle">');
  
    $('#nav-toggle').click(function(){
      $('#header nav').toggle();
    });

    $('#search-toggle').click(function(){
      $('#header #search').toggle();
    });
  });
}

if (window.innerWidth < 600) {
  var head = document.getElementsByTagName('head')[0];
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'funkce/jquery.min.js';
  script.onload = thejqueryfunction;
  head.appendChild(script);
}