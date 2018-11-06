import axios from 'axios';

export default {
  getMap(state, url) {
    axios
      .get(url)
      .then(res => {
        state.commit('setupMap', res.data.data);
      });
  },
  createMarker(state, payload) {
    const { url, data } = payload;

    return axios.post(url, {
      map_marker: data,
    });
  },
  patchMarker(state, payload) {
    const { url, data } = payload;

    return axios.patch(url, {
      map_marker: data,
    });
  },
};
