import * as types from '../mutation-types'

// state
export const state = {
  bgColor: '#fff',
  showActivities: true,
}

// getters
export const getters = {
  bgColor: state => state.bgColor,
  showActivities: state => state.showActivities,
}

// mutations
export const mutations = {
  [types.SET_BGCOLOR] (state, { color }) {
    state.bgColor = color
  },

  [types.SET_SHOW_ACTIVITIES] (state, { value }) {
    state.showActivities = value
  }
}

// actions
export const actions = {
  setBgColor ({ commit }, { color }) {
    commit(types.SET_BGCOLOR, { color })
  },

  setShowActivity ({ commit }, { value }) {
    commit(types.SET_SHOW_ACTIVITIES, { value })
  }
}
