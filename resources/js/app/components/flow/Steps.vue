<template lang="html">
  <div class="steps">
    <template v-for="n in Array.from(Array(steps).keys()).map(z => z + 1)">
      <div
        class="step"
        v-bind:class="{
          'is-current': n == step,
          'is-active': n < step,
          'selected': value == n,
        }"
        @click="select(n)"
      >
        <span v-if="n != steps">{{ n }}</span>
        <i v-else class="icon ion-ios-checkmark"></i>
      </div>
    </template>
    <div class="stripe">
      <div
        v-for="n in Array.from(Array(steps - 1).keys())"
        v-bind:class="{ active: n < step - 1 }"
        class="item"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'steps',
  props: {
    step: {},
    value: {
      required: true,
    },
  },
  methods: {
    select(n) {
      if(n > this.step) return;
      this.$emit('input', n);
    }
  },
  data() {
    return {
      steps: 5
    };
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.steps {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  z-index: 1;
  position: relative;
  .stripe {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    z-index: -1;
    .item {
      background: $clr-d-gray;
      height: 2px;
      flex-grow: 1;
      &.active {
        background: $clr-green;
      }
    }
  }
  .step {
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 25px;
    border: 2px solid $clr-d-gray;
    font-weight: 500;
    font-size: 22px;
    color: $clr-d-gray;
    text-align: center;
    line-height: 50px;
    user-select: none;
    position: relative;
    background: #fff;
    i {
      font-size: 36px;
    }
    &.is-current {
      color: $clr-green;
      border-color: $clr-green;
    }
    &.is-active {
      background: $clr-green;
      transform: scale(1.1);
      color: #fff;
      font-weight: bold;
      border-color: $clr-green;
    }
    &.selected {
      &::before {
        position: absolute;
        left: -12px;
        top: -12px;
        width: 70px;
        height: 70px;
        content: '';
        border-radius: 35px;
        border: 4px solid $clr-green;
      }
    }
  }
}

</style>
