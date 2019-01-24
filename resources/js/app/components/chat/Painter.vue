<template lang="html">
  <div class="painter">
    <div class="dark-bg" @click="$emit('close')"></div>
    <div class="modal" ref="modal">
      <button class="ok-btn" @click="$emit('close')">
        <i class="icon ion-ios-checkmark"></i>
      </button>
      <div class="buttons">
        <button class="clear-ctx" @click="clearPaint">
          <i class="icon ion-ios-undo"></i>
        </button>
        <div class="color-selector">
          <button @click="colors_opened = !colors_opened">
            <i class="icon ion-ios-brush"></i>
          </button>
          <div v-if="colors_opened" class="colors">
            <button
              class="color red"
              @click="selectColor('#f00')"
              v-bind:class="{ selected: color == '#f00' }"
            ></button>
            <button
              class="color yellow"
              @click="selectColor('#ff0')"
              v-bind:class="{ selected: color == '#ff0' }"
            ></button>
          </div>
        </div>
      </div>
      <canvas
        @mousedown="onMouseDown"
        @mousemove="onMouseMove"
        @mouseup="paintOff"
        @mouseleave="paintOff"
        ref="canvas"
        width="400"
        height="400"
      ></canvas>
    </div>
  </div>
</template>

<script>
export default {
    name: 'painter',
    props: {
      image: {
        required: true,
      }
    },
    data() {
      return {
        painting: false,
        ctx: null,
        lx: 0,
        ly: 0,
        color: '#f00',
        colors_opened: false,
        parsed_img: null,
      };
    },
    methods: {
      onMouseDown(e) {
        var rect = e.target.getBoundingClientRect();
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top;

        this.ctx.lineCap = 'round';
        this.ctx.lineWidth = 5;
        this.ctx.lineJoin = 'round';
        this.ctx.strokeStyle = this.color;
        this.ctx.beginPath();
        this.ctx.moveTo(mouseX, mouseY);
        this.ctx.stroke();
        this.lx = mouseX;
        this.ly = mouseY;
        this.painting = true;
      },
      onMouseMove(e) {
        if(!this.painting) return;

        var rect = e.target.getBoundingClientRect();
        var mouseX = e.clientX - rect.left;
        var mouseY = e.clientY - rect.top;

        // || !(Math.sqrt(Math.pow(this.lx - mouseX, 2) + Math.pow(this.ly - mouseY, 2)) > 3)

        this.ctx.beginPath();
        this.ctx.moveTo(this.lx, this.ly);
        this.ctx.lineTo(mouseX, mouseY);
        this.ctx.closePath();
        this.ctx.stroke();
        this.lx = mouseX;
        this.ly = mouseY;
      },
      paintOff() {
        if(!this.painting) return;

        this.ctx.closePath();
        this.ctx.stroke();
        this.painting = false;
        this.$emit('input', this.$refs.canvas.toDataURL('image/jpeg'));
      },
      clearPaint() {
        this.renderImage(this.parsed_img);
      },
      selectColor(color) {
        this.color = color;
      },
      processImage(image) {
        console.log(image);
        if(image) {
          var reader = new FileReader();
          reader.addEventListener('load', () => {
            var img = new Image();
            img.src = reader.result;
            img.onload = () => this.renderImage(img);
          }, false);
          reader.readAsDataURL(image);
        }
      },
      renderImage(img) {
        this.parsed_img = img;
        this.ctx.clearRect(0, 0, 520, 520);
        this.drawImageContain(this.ctx, img);
        this.$emit('input', this.$refs.canvas.toDataURL('image/jpeg'));
      },
      drawImageContain(ctx, img) {
        var canvas = ctx.canvas;
        var hRatio = canvas.width  / img.width    ;
        var vRatio =  canvas.height / img.height  ;
        var ratio  = Math.min ( hRatio, vRatio );
        var centerShift_x = ( canvas.width - img.width*ratio ) / 2;
        var centerShift_y = ( canvas.height - img.height*ratio ) / 2;
        ctx.clearRect(0,0,canvas.width, canvas.height);
        ctx.drawImage(
          img, 0,0, img.width, img.height,
          centerShift_x, centerShift_y, img.width*ratio, img.height*ratio
        );
      },
      onEscape(e) {
        if(e.which == 27) {
          this.$emit('close');
          document.removeEventListener('keyup', this.closeSelection);
        }
      }
    },
    watch: {
      image(to, from) {
        if(to)
          this.processImage(to);
        else {
          this.ctx.clearRect(0, 0, 520, 520);
          this.$emit('input', null);
        }
      }
    },
    mounted() {
      this.ctx = this.$refs.canvas.getContext('2d');
      this.ctx.clearRect(0, 0, 520, 520);
      document.removeEventListener('keyup', this.onEscape);
      document.addEventListener('keyup', this.onEscape);
    },
    destroyed() {
      document.removeEventListener('keyup', this.onEscape);
    }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.painter {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
  .modal {
    position: relative;
    width: 400px;
    height: 400px;
    background: #000;
    border-radius: 10px;
    z-index: 1;
    transition: width 0.3s ease, height 0.3s ease;
    .ok-btn {
      position: absolute;
      width: 20px;
      height: 20px;
      right: -10px;
      bottom: -10px;
      border-radius: 10px;
      background: $clr-green;
      color: #fff;
    }
    .buttons {
      left: -50px;
      top: 0;
      width: 40px;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: absolute;
      > button, .color-selector > button {
        width: 40px;
        height: 40px;
        font-size: 22px;
        border-radius: 50%;
        margin: 10px 0;
      }
      .clear-ctx {
        background: $clr-d-pink;
        color: #fff;
      }
      .color-selector {
        position: relative;
        > button {
          background: $clr-d-pink;
          color: #fff;
        }
        .colors {
          position: absolute;
          right: calc(100% + 5px);
          top: 50%;
          height: 20px;
          display: flex;
          flex-direction: row;
          justify-content: flex-end;
          margin-top: -10px;
          .color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin: 0 5px;
            transition: transform 0.3s ease;
            &.yellow {
              background: #ff0;
            }
            &.red {
              background: #f00;
            }
            &.selected {
              transform: scale(1.3);
            }
          }
        }
      }
    }
  }
  .dark-bg {
    cursor: pointer;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    position: absolute;
    background: rgba(0,0,0,0.8);
  }
}

</style>
