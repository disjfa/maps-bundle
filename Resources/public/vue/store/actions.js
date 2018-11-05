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

    const formData = new FormData();
    Object.keys(data).forEach(function (key) {
      formData.set(`map_marker[${key}]`, data[key]);
    });

    return axios.post(url, formData);
  },
  patchMarker(state, payload) {
    const { url, data } = payload;

    return axios.patch(url, {
      map_marker: data,
    });
  },
};
