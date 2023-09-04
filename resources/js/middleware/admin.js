import store from '../store'

export default (to, from, next) => {
  if (store.getters['auth/user'].roles !== 'admin') {
    next({ name: 'home' })
  } else {
    next()
  }
}