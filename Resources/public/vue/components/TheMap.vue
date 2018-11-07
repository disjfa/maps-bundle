<template>
  <div style="position: relative;">
    <template v-if="map.id">
      <l-map
        style="height: 70vh"
        :zoom="zoom"
        :center="center"
        ref="map"
      >
        <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>

        <l-marker v-if="editing" :lat-lng="marker" :draggable="true" ref="marker"></l-marker>

        <template v-if="!editing">
          <l-marker v-for="marker in markers" :lat-lng="[marker.center_lat, marker.center_lng]" :key="marker.id">
            <l-popup>
              <h3>{{ marker.title }}</h3>
              <div>{{marker.description}}</div>
              <div v-if="marker.links.patch_url">
                <hr>
                <a href="#" @click="editMarker(marker)">Edit</a>
              </div>
            </l-popup>
          </l-marker>
        </template>
      </l-map>
    </template>
    <div class="popup">
      <div v-if="editing">
        <a href="#" class="close" @click.prevent="stopEditing">
          <span aria-hidden="true">&times;</span>
        </a>
        <div class="form-group">
          <label for="map-title">Titel</label>
          <input id="map-title" type="text" class="form-control" v-model="currentMarker.title">
        </div>
        <div class="form-group">
          <label for="map-description">Description</label>
          <textarea id="map-description" class="form-control" v-model="currentMarker.description"></textarea>
        </div>
        <div class="form-group">
          <button @click="saveMarker" class="btn btn-primary">
            <i class="fas fa-save"></i> save
          </button>
        </div>
      </div>
      <div v-else>
        <a v-if="map.links && map.links.patch_url" href="#" @click.prevent="saveMapLocation" class="btn btn-primary btn-block">
          <i class="fas fa-save"></i>
          Save location
        </a>
        <a v-if="map.links && map.links.create_marker_url" href="#" @click.prevent="createMarker" class="btn btn-primary btn-block">
          <i class="fas fa-plus"></i>
          Add marker
        </a>
        <a href="#" @click.prevent="mapCenter" class="btn btn-primary btn-block">
          <i class="fas fa-expand-arrows-alt"></i>
          Center
        </a>
      </div>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue';
  import { LMap, LTileLayer, LMarker, LPopup } from 'vue2-leaflet';
  import L from 'leaflet';
  import _ from 'underscore';

  delete L.Icon.Default.prototype._getIconUrl;
  L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png')
  });

  export default {
    name: 'the-map',
    components: {
      LMap,
      LTileLayer,
      LMarker,
      LPopup
    },
    props: {
      mapUrl: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        editing: false,
        zoom: 13,
        lat: 47.413220,
        lng: -1.219482,
        currentMarker: {
          title: '',
          description: '',
        }
      }
    },
    mounted() {
      this.$store.dispatch('map/getMap', this.mapUrl);
    },
    watch: {
      map: function (map, previousMap) {
        if (map.id === previousMap.id) {
          return;
        }

        const { zoom, center_lat, center_lng } = map;
        const center = L.latLng(center_lat, center_lng);

        Vue.set(this, 'lat', center_lat);
        Vue.set(this, 'lng', center_lng);
        this.lat = center_lat;
        this.lat = center_lng;

        this.$nextTick(() => {
          const { mapObject } = this.$refs.map;

          if (center_lat && center_lng && zoom) {
            mapObject.setView(center, zoom);
          }
        });
      },
    },
    computed: {
      center() {
        return [this.lat, this.lng];
      },
      markers() {
        return this.$store.getters['map/getMarkers'];
      },
      map() {
        return this.$store.getters['map/getMap'];
      }
    },
    methods: {
      saveMapLocation: function () {
        const { mapObject } = this.$refs.map;
        const { lat, lng } = mapObject.getCenter();
        const data = {
          zoom: mapObject.getZoom(),
          centerLat: lat,
          centerLng: lng,
        };

        const { patch_url } = this.map.links;
        this.$store.dispatch('map/patchMap', {
          url: patch_url,
          data,
        });
      },
      stopEditing: function () {
        this.editing = false;
      },
      createMarker: function () {
        const { mapObject } = this.$refs.map;
        this.marker = mapObject.getCenter();
        this.currentMarker = {
          title: '',
          description: '',
        };
        this.editing = true;
      },
      editMarker: function (marker) {
        this.marker = [marker.center_lat, marker.center_lng];
        this.currentMarker = _.clone(marker);
        this.editing = true;
      },
      saveMarker: function () {
        const { title, description, id, links } = this.currentMarker;
        const { mapObject } = this.$refs.marker;
        const { lat, lng } = mapObject.getLatLng();

        const data = {
          title,
          description,
          map: this.map.id,
          centerLat: lat,
          centerLng: lng,
        };

        if (id) {
          const { patch_url } = links;
          if (!patch_url) {
            return;
          }

          this.$store.dispatch('map/patchMarker', {
            url: patch_url,
            data,
          }).then(() => {
            this.editing = false;
            this.$store.dispatch('map/getMap', this.mapUrl);
          });
        } else {
          const { create_marker_url } = this.map.links;
          if (!create_marker_url) {
            return;
          }

          this.$store.dispatch('map/createMarker', {
            url: create_marker_url,
            data,
          }).then(() => {
            this.editing = false;
            this.$store.dispatch('map/getMap', this.mapUrl);
          });
        }
      },
      mapCenter: function () {
        const bounds = [];
        this.markers.forEach(marker => {
          bounds.push(L.latLng(marker.center_lat, marker.center_lng))
        });
        const latLngBounds = L.latLngBounds(bounds).pad(.1);

        const { mapObject } = this.$refs.map;
        mapObject.fitBounds(latLngBounds);
      }
    }
  }
</script>

<style type="scss">
  @import "../../../../../../../node_modules/leaflet/dist/leaflet.css";

  .leaflet-container {
    font: inherit;
  }

  .leaflet-popup-content {
    line-height: inherit;
  }

  .leaflet-container a {
    color: inherit;
  }

  .popup {
    padding: 10px;
    background: #fbfbfb;
  }

  @media only screen and (min-width: 768px) {
    .popup {
      position: absolute;
      bottom: 25px;
      right: 10px;
      z-index: 500;
    }
  }

</style>
