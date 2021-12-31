export default function ({ $auth, redirect, store }) {
  if ($auth.user && $auth.hasScope('admin')) {
    //let go
  } else {
    store.dispatch(
      'alert/showAlert',
      "You don't have access go to this page! (Admin)"
    )
    return redirect('/')
  }
}
