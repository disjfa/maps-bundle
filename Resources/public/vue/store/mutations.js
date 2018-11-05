import Vue from 'vue';

export default {
  setupMap(state, payload) {
    const { markers } = payload;

    delete payload.markers;

    Vue.set(state, 'map', payload);
    Vue.set(state, 'markers', markers.data);
  },
};
