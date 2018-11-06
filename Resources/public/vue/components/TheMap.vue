<template>
  <div style="height: 70vh; position: relative;">
    <l-map
      style="height: 70vh"
      v-if="map"
      @update:bounds="setBounds"
      @update:center="setCenter"
      @update:zoom="setZoom"
      :zoom="zoom"
      :center="center"
      ref="map"
    >
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>

      <l-marker v-if="editing" :lat-lng="marker" :draggable="true" ref="marker"></l-marker>

      <template v-if="!editing">
        <l-marker v-for="marker in markers" :lat-lng="[marker.center_lat, marker.center_lng]" :key="marker.id">
          <l-popup>
            <p>{{ marker.title }}</p>
            <a href="#" class="btn btn-link" v-if="marker.links.patch_url" @click="editMarker(marker)">Edit</a>
          </l-popup>
        </l-marker>
      </template>
    </l-map>

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
          <button @click="saveMarker">save</button>
        </div>
      </div>
      <div v-else>
        <a v-if="map.links && map.links.create_marker_url" href="#" @click.prevent="createMarker" class="btn btn-primary btn-block">
          <i class="fas fa-expand-arrows-alt"></i>
          Center
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
  import { LMap, LTileLayer, LMarker, LPopup } from 'vue2-leaflet';
  import L from 'leaflet';

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
      zoom: {
        type: Number,
        default: 13
      },
      lat: {
        type: Number,
        default: 47.413220
      },
      lng: {
        type: Number,
        default: -1.219482
      },
    },
    data() {
      return {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        editing: false,
        marker: [41, 1],
        currentMarker: {
          title: '',
        }
      }
    },
    mounted() {
      this.$store.dispatch('map/getMap', this.mapUrl);
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
      stopEditing: function() {
        this.editing = false;
      },
      createMarker: function() {
        const { mapObject } = this.$refs.map;
        this.marker = mapObject.getCenter();
        this.currentMarker = {
          title: '',
        };
        this.editing = true;
      },
      editMarker: function (marker) {
        this.marker = [marker.center_lat, marker.center_lng];
        this.currentMarker = marker;
        this.editing = true;
      },
      saveMarker: function () {
        const { currentMarker } = this;
        const { mapObject } = this.$refs.marker;
        const { lat, lng } = mapObject.getLatLng();

        const data = {
          title: currentMarker.title,
          map: this.map.id,
          centerLat: lat,
          centerLng: lng,
        };

        if (currentMarker.id) {
          const { patch_url } = currentMarker.links;
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
      setBounds: (e) => {
        console.log(L);
      },
      setZoom: (e) => {
        console.log(e);
      },
      setCenter: (e) => {
        console.log(e);
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
    position: absolute;
    bottom: 25px;
    right: 10px;
    background: pink;
    z-index: 500;
    padding: 10px;
  }

</style>
