import _ from 'underscore';

export default {
  getMap: state => _.clone(state.map),
  getMarkers: state => _.clone(state.markers),
};
