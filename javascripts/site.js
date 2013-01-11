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
    $('.caption').hover(
      function(){ //mouseEnter
        $('.details').slideDown(200);
      },
      function(){ //mouseLeave
        $('.details').slideUp(200);
      }
    );
  }
});

axis.Views.FeatureItem = Backbone.View.extend({

});

axis.Views.NewsFeed = Backbone.View.extend({

});

axis.Views.NewsFeedItem = Backbone.View.extend({

});

axis.Views.ToolsAndData = Backbone.View.extend({

});

axis.Views.ToolsAndDataItem = Backbone.View.extend({

});

axis.Views.ArticleContainer = Backbone.View.extend({

});

axis.Views.Article = Backbone.View.extend({

});

axis.Views.ArticleSidebar = Backbone.View.extend({

});

axis.Views.RecentArticle = Backbone.View.extend({
 
});

axis.Views.ArticleNavigationItem = Backbone.View.extend({

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
    if(document.URL.search('/project/') !== -1) {
      axis.ProjectContainer = new axis.Views.ProjectContainer({el: '#news-container'});
    } else if (document.URL.search('/article/') !== -1) {

    }
  }
});

$(document).ready(function() {
  axis.router = new axis.Router();
});