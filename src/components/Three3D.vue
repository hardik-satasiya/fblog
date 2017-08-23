<template>
    <div :class="getComponentName() + '_view_port _view_port'">
        <div id="render_output" class="view_area">

        </div>
        <input type="text" v-model="rotaion" />
    </div>
</template>

<script>
import extension from '../extendedComponenet'
import { EventBus } from '../eventBus'
import * as THREE from 'three'

export default {
    extends: extension,
    name: 'three3d',
    data () {
        return {
            rotaion: 0.10
        }
    },
    created() {
        EventBus.$emit('Navigation::hideSubnav')
    },
    mounted() {
        this.basicSetup()
    },
    destroyed() {
        EventBus.$emit('Navigation::showSubnav')
    },
    methods: {
        basicSetup() {

            var self = this
            var scene, camera, renderer;
            var geometry, material, mesh;
            var render_output = document.getElementById('render_output')

            console.log('asdfasdf');

            scene = new THREE.Scene();

            camera = new THREE.PerspectiveCamera( 75, render_output.clientWidth / render_output.clientHeight, 1, 10000 );
            camera.position.z = 1000;

            geometry = new THREE.BoxGeometry( 200, 200, 200 );
            material = new THREE.MeshBasicMaterial( { color: 0xff0000, wireframe: true } );

            mesh = new THREE.Mesh( geometry, material );
            scene.add( mesh );

            renderer = new THREE.WebGLRenderer();

            renderer.setSize( render_output.clientWidth , render_output.clientHeight );

            render_output.appendChild( renderer.domElement );

            // renderer.setSize( window.innerWidth, window.innerHeight );
            // document.body.appendChild( renderer.domElement );
            var animate = function animate() {
                requestAnimationFrame( animate )

                mesh.rotation.x += 0.1
                if(parseFloat(self.rotaion) > 0) {
                    mesh.rotation.y += parseFloat(self.rotaion);
                }

                renderer.render( scene, camera )

            }
            animate()
        }
   }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
._view_port {
    text-align: center;;
}
.view_area {
    height: 500px;
    width:500px;
    margin: 0 auto;
}
</style>