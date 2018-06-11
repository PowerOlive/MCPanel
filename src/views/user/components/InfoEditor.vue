<template>
  <public-box icon="wrench" :title="$t('user.info_editor_box.edit_profile')" class="user-info-editor">
    <el-form :model="form" ref="form" label-width="80px" label-position="top">
      <el-row :gutter="20">
        <el-col :xs="24" :md="12">
          <el-form-item :label="$t('user.info_editor_box.username')">
            <el-input v-model="form.username" disabled></el-input>
          </el-form-item>
        </el-col>
        <el-col :xs="24" :md="12">
          <el-form-item :label="$t('user.info_editor_box.nickname')">
            <el-input v-model="form.nickname"></el-input>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :xs="24" :md="12">
          <el-form-item label="性别">
            <el-select v-model="form.gender" width="100%">
              <el-option label="男" value="male"></el-option>
              <el-option label="女" value="female"></el-option>
              <el-option label="MtF" value="mtf"></el-option>
              <el-option label="FtM" value="ftm"></el-option>
              <el-option label="CD" value="cd"></el-option>
              <el-option label="保密" value=""></el-option>
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
      <el-form-item>
        <el-button type="primary">{{ $t('user.info_editor_box.save') }}</el-button>
      </el-form-item>
    </el-form>
  </public-box>
</template>

<script>
import { PublicBox } from '@/components'
export default {
  name: 'info-editor',
  data() {
    return {
      form: {
        username: 'Caringor',
        nickname: '萌王千岁',
        gender: 'male'
      }
    }
  },
  components: { PublicBox }
}
</script>

<style lang="less">
  .user-info-editor {
    margin: 0 0 16px 0;
    .el-select {
      width: 100%;
    }
  }
</style>

