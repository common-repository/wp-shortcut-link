var data = {
            action: 'save_wpslab_get_list_cat',
           
           
           // nonce:nonce
        };
        $.post(the_in_url.in_url, data, function(response) {
      var  dataces = $.parseJSON(response).wpslab;

          for (var i = 0; i < dataces.length; i++) {
        
    var shortcode =  "[wpslab id="+dataces[i].id_wpslab+"]";
    
if (dataces[i].title) {

wp.blocks.registerBlockType('wpcat-embed-shortcode/box-'+i, {
  title: dataces[i].title,
  icon: 'smiley',
  category: 'mycategory',
  attributes: {
    content: {type: 'string',source: 'html'},
    color: {type: 'string'}
  },
  
  
  edit: function(props) {
   

    return React.createElement(
      "div",
      null,
       shortcode
      
    
    );
  },
  save: function(props) {
    
    return wp.element.createElement(
      "div",
     null,
       shortcode
    );
  }
})

}
}
  });