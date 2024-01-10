'use strict'

{
  const list_styles = document.querySelectorAll('.list-style');
  for(let i = 0; i < list_styles.length; i++){
    let list_style = list_styles[i];
    list_style.addEventListener('click', function() {
      list_style.parentNode.submit();
      console.log('click');
    });
  }
}