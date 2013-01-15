function get_category_info(){
  $project_parent_category = get_category_by_slug('project');
  $project_parent_category_id=$project_parent_category->term_id;
  $categories=get_the_category();
  foreach($categories as $category){
    if($category->category_parent==$project_parent_category_id){
      $c_name = $category->name;
      $c_slug = $category->slug; 
      $c_number = $category->cat_ID; 
}