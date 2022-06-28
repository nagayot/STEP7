// 登録時
function checkSubmit(){
  if(window.confirm('登録してよろしいですか？')){
      return true;
  } else {
      return false;
  }
}

// 削除時
function checkDelete(){
  if(window.confirm('削除してよろしいですか？')){
      return true;
  } else {
      return false;
  }
}

// 更新時
function checkUpdate(){
  if(window.confirm('更新してよろしいですか？')){
      return true;
  } else {
      return false;
  }
}