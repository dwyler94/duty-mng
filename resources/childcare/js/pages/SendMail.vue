<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header calendar-title">
                            <h3 class="card-title mb-0">{{ office.name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <label for="inputEmail3" class="col-form-label col-md-1 text-justify-content">宛先</label>
                                <div class="col-md-2 d-flex text-justify-content">
                                    <div class="align-self-center">
                                        {{ office.name }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2"></div>
                                <div class="col-md-2">
                                    <select class="form-control" v-model="childrenClassId" @change="getParents()">
                                        <option :value="0">全保護者</option>
                                        <option v-for="claz in childrenClasses" :key="claz.id" :value="claz.id">{{ claz.name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" v-model="childId">
                                        <option :value="0">全保護者</option>
                                        <option v-for="parent in parents" :key="parent.id" :value="parent.id">{{ parent.name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" v-model="type" :class="{'is-invalid': error.type}" @change="templateChanged">
                                        <option :value="0">通常メール</option>
                                        <option :value="1">緊急メール</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6"></div>
                                <div class="col-md-2">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" multiple @change="onFileChange">
                                        <label class="custom-file-label" for="exampleInputFile">添付</label>
                                    </div>
                                    <div class="mt-1">
                                        <div v-for="(file, index) in files" :key="index">
                                            <label style="width:80%" class="text-cut">{{file.name}}</label>
                                            <button class="btn btn-primary float-right" @click="removeFile(index)">X</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <label for="inputEmail3" class="col-form-label col-md-1 text-justify-content">件名</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder=""
                                        v-model="subject"
                                        :class="{'is-invalid': error.subject}"
                                        @input="() => {error.subject = null;}"
                                    />
                                    <div v-if="error.subject" class="validation-error">
                                        {{ error.subject }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-1"></div>
                                <div class="col-md-7">
                                    <textarea class="form-control" rows="20"
                                        v-model="content"
                                        :class="{'is-invalid': error.content}"
                                        @input="() => {error.content = null;}"
                                    >
                                    </textarea>
                                    <div v-if="error.content" class="validation-error">
                                        {{ error.content }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <button class="btn btn-primary float-right" @click="sendMail">送信</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import moment from 'moment';
import { mapState } from 'vuex';
import api, { apiErrorHandler } from '../global/api';
import { showSuccess } from '../helpers/error';
import actionLoading from '../mixin/actionLoading';

export default {
    mixins: [actionLoading],
    data() {
        return {
            childrenClassId: 0,
            content: '',
            subject: '',
            type: 0,
            error: {
                subject: null,
                type: null,
                content: null,
                files: null,
            },
            files: [],
            parents: [],
            childId: 0,
        }
    },
    computed: {
        ...mapState({
            childrenClasses: state => state.constants.childrenClasses,
            office: state => state.session.info.office
        }),
    },
    mounted() {
        this.childrenClassId = this.$route.query.childrenClassId;
        this.type = this.$route.query.type;
        this.fetchData();
        this.getParents();
    },
    methods: {
        templateChanged(e) {
            this.type = e.target.value;
            this.fetchData();
            this.subject = '';
            if(this.type == 1) {
                this.subject = this.$t("Urgent mail title");
            }
        },
        sendMail() {
            if (!this.validate()) return;
            if (!confirm(this.$t("Are you sure you want to send mail?"))) return;
            this.setActionLoading();
            let data = new FormData();
            data.append('subject', this.subject);
            data.append('content', this.content);
            if(this.childrenClassId && this.childrenClassId !== '0') {
                data.append('children_class_id', this.childrenClassId);
            }
            data.append('type', this.type);
            if(this.childId && this.childId !== '0') {
                data.append('child_id', this.childId);
            }
            if(this.files && this.files.length > 0) {
                for (var i = 0; i < this.files.length; i++) {
                    let file = this.files[i];
                    data.append('file_' + (i+1), file);
                }
            }

            let header = {
                'Content-Type' : 'multipart/form-data'
            }
            api.post('mail', header, data)
            .then(() => {
                showSuccess(this.$t('Successfully saved'));
                this.$router.push('/mail-table')
            })
            .catch(apiErrorHandler)
            .finally(() => this.unsetActionLoading())
        },
        validate() {
            let valid = true;
            this.error = {};
            if (!this.subject) {
                this.error.subject = this.$t('Please input subject');
                valid = false;
            }
            if (this.subject && this.subject.length > 190) {
                this.error.subject = this.$t('Please enter 190 characters or less');
                valid = false;
            }
            if (!this.content) {
                this.error.content = this.$t('Please input content');
                valid = false;
            }
            return valid;
        },
        fetchData() {
            this.setActionLoading();
            api.get('mail-template', null, {type: this.type})
            .then((response) => {
                this.content = response.content;
            })
            .catch(apiErrorHandler)
            .finally(() => {
                this.unsetActionLoading();
            })
        },
        getParents() {
            let query = {};
            if(this.childrenClassId && this.childrenClassId !== '0') {
                query = {class_id: this.childrenClassId};
            }
            api.get('child', null, query)
            .then((response) => {
                this.parents = response.filter(item => item.admissionDate == null || item.admissionDate <= moment(new Date()).format("YYYY-MM-DD"));
            })
            .catch(apiErrorHandler)
        },
        onFileChange(e) {
            var fileData = e.target.files;
            if (!fileData || fileData.length < 1) return;
            // if (fileData.length > 10)
            //     this.errors.files = this.$t('Please attach 10 files or less');
            if (this.files && this.files.length > 0) {
                this.files.push(...fileData);
            } else {
                this.files = [...fileData];
            }
            if (this.files.length > 10)
                this.files.splice(0,  this.files.length - 10);
        },
        removeFile(index) {
            this.files.splice(index, 1);
        }
    }
};
</script>
