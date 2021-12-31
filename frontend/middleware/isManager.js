export default function ({ $auth, redirect, store }) {
  if (
    $auth.user &&
    ($auth.hasScope('admin') ||
      $auth.hasScope('roomManager') ||
      $auth.hasScope('groupManager'))
  ) {
    //let go
  } else {
    store.dispatch(
      'alert/showAlert',
      "You don't have access go to this page! (Admin or Manager)"
    )
    return redirect('/')
  }
}
