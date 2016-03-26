var $ = jQuery;
var _ = _;

function selva_js_app( settings ){
  var self = this;

  self.settings = settings;
  self.newest_post = {ID: 0};
  self.last_modified_tz = new Date();

  self.insert_post = function( post_data ) {
    console.log("Inserting new post" , post_data );
    self.newest_post = post_data;

    var context = $('#main');
    var post_html =  $(
      _.template(
        '<article id="post-<%= ID %>" class="post type-post format-standard hentry selva-animated">' +
        "<header class='entry-header'>" +
        " <h2 class='entry-title'><a href='<%= link %>'><%= title %></a></h2>" +
        "</header>" +
        "<div class='entry-content'>" +
        "<%= content %>" +
        "</div>" +
        '<footer class="entry-footer">' +
        '<span class="posted-on"><span class="screen-reader-text>Posted on</span>"' +
        '<%= date %></span>' +
        '<span class="comments-link"><a href="#"><%= status %></a></span>' +
        '</footer>' +
        '</article>' , self.newest_post )
    );

    if( self.settings.insert_top ){
      $(context).prepend( post_html.get(0) );
    } else {
      $(context).append( post_html.get(0) )
    }
  }

  self.on_new_post = function( new_post ){
    if( self.settings.autoload_enabled ){
      self.insert_post( new_post );
    } else {
      console.log("New posts arrived...");
    }

    if( self.settings.sound_enabled){
      self.play_sound()
    }
  }

  self.load_bleep = function(){
    self.bleep = $( '<audio id="bleep" src="' + self.settings.bleep_url + '" preload="auto"></audio>' );
    $('body').append( self.bleep );
  }

  self.play_sound = function(){
    var au = self.bleep.get(0)
    au.currentTime = 0.0;
    au.play();
  }

  self.ask_for_new_posts = function(){
    self.postsCollection.fetch( { data: {
        filter: {'orderby': 'date', 'order': 'DESC'},
        per_page: 5
      }}).done( function(){

        // Check head post, if different than previous, trigger `on_new_post`
        var new_post = self.postsCollection.at(0).attributes;
        if( new_post.ID != self.newest_post.ID ){
          self.on_new_post( new_post )
        }
      });
  }

  self.autoreload = function( interval ){
    console.log("Enabling auto-reload every ", interval , " seconds")
    function reload(){ window.location.reload();}
    self.autoreload_timer = setInterval( reload, interval*1000 )
  }

  self.ja_chegamos = function(){
    console.log("Checking new posts every ", self.settings.interval , " seconds")
    self.autoload_timer = setInterval( self.ask_for_new_posts , self.settings.interval * 1000)
  }


  //if(self.settings.autoreload_enabled){
  //self.autoreload( self.settings.interval );
  //}
  self.postsCollection = new wp.api.collections.Posts();
  self.postsCollection.fetch({ data: { filter: {'orderby': 'date', 'order': 'DESC'} }})
                      .done( function(){
                          self.newest_post = self.postsCollection.at(0).attributes;
                      });

  self.load_bleep();
  self.ja_chegamos();

  return this;
}

jQuery(document).ready( function($) {
  // Cast settings from [php] to js
  selva.autoreload_enabled = (selva.autoreload_enabled == "1");
  selva.autoload_enabled = (selva.autoload_enabled == "1");
  self.sound_enabled = (selva.sound_enabled == "1");
  selva.insert_top = (selva.insert_top == "1");
  selva.interval  = parseInt( selva.interval );

  window.selva_app = new selva_js_app(  selva  )
/*
  var postsCollection = new wp.api.collections.Posts();
  console.log( postsCollection.fetch() );
  console.log( postsCollection );
*/
})
