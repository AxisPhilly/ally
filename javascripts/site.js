if (typeof axis === 'undefined' || !axis) {
  var axis = {};
  axis.Views = {};
  axis.Models = {};
  axis.Collections = {};
}

axis.Models.Article = Backbone.Model.extend({

});

axis.Collections.Articles = Backbone.Collection.extend({
  model: axis.Models.Article
});

axis.Views.Header = Backbone.View.extend({
  initialize: function() {
    // Adpated from http://happycog.com/ fixed bar
    var $navBar = $('.nav-container'),
        $spacer = $('<div />', {
          "class": "navBar-drop-spacer",
          "height": $navBar.outerHeight()
        });
    
    $(window).scroll(function () {
      if (!$navBar.hasClass('affix') && $(window).scrollTop() > $navBar.offset().top) {
        $navBar.before($spacer);
        $navBar.addClass("affix");
      }
      else if ($navBar.hasClass('affix')  && $(window).scrollTop() < $spacer.offset().top) {
        $navBar.removeClass("affix");
        $spacer.remove();
      }
    });
  }
});

axis.Views.Index = Backbone.View.extend({

});

axis.Views.ProjectContainer = Backbone.View.extend({
  initialize: function() {
    this.features = new axis.Views.Features({el: "#feature-container"});
  }
});

axis.Views.Features = Backbone.View.extend({
  initialize: function() {
    this.initOrbit();
    this.initSlider();
  },

  initOrbit: function() {
    $('#featured').orbit({
      timer: 'true',
      advanceSpeed: 6000,
      animationSpeed: 1000,
      pauseOnHover: true,
      startClockOnMouseOut: true,
      startClockOnMouseOutAfter: 1
    });
  },

  initSlider: function() {
    $('#featured .caption').hover(
      function(){ //mouseEnter
        if ($(document).width() < 376) { return false; }
        $('.details').slideDown(200);
      },
      function(){ //mouseLeave
        if ($(document).width() < 376) { return false; }
        $('.details').slideUp(200);
      }
    );
  }
});


axis.Router = Backbone.Router.extend({
  routes: {
    "/article/:slug/": "article",
    "/project/:slug/": "project"
  },

  initialize: function() {
    window.addEventListener('load', function() {
      new FastClick(document.body);
    }, false);

    this.initViews();
  },

  initViews: function() {
    axis.Header = new axis.Views.Header({el: '.banner'});

    if(document.URL.search('/project/') !== -1) {
      axis.ProjectContainer = new axis.Views.ProjectContainer({el: '#news-container'});
    } else if (document.URL.search('/article/') !== -1) {

    } else {
      axis.Index = new axis.Views.Index({el: '#news-container'});
    }
  }
});

$(document).ready(function() {
  axis.router = new axis.Router();
});