<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-b border-[#eff0f6] p-5 flex items-center justify-between">
                    <h3 class="font-bold text-lg text-heading">{{ $t('menu.backup') }}</h3>
                    <button type="button" @click="createBackup" :disabled="loading" class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-medium transition hover:bg-primary-dark">
                        <span v-if="loading" class="fa fa-spinner fa-spin mr-2"></span>
                        <span v-else class="fa fa-plus mr-2"></span>
                        {{ $t('button.create_backup') }}
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table w-full text-left">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="p-4 font-medium text-gray-500">{{ $t('label.filename') }}</th>
                                    <th class="p-4 font-medium text-gray-500">{{ $t('label.size') }}</th>
                                    <th class="p-4 font-medium text-gray-500">{{ $t('label.tables') }}</th>
                                    <th class="p-4 font-medium text-gray-500">{{ $t('label.date') }}</th>
                                    <th class="p-4 font-medium text-gray-500 text-right">{{ $t('label.action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-if="loading && backups.length === 0">
                                    <td colspan="5" class="p-4 text-center">
                                        <i class="fa fa-spinner fa-spin text-primary"></i>
                                    </td>
                                </tr>
                                <tr v-for="(backup, index) in backups" :key="index" class="hover:bg-gray-50 transition">
                                    <td class="p-4 font-medium text-heading">
                                        {{ backup.filename }}
                                    </td>
                                    <td class="p-4 text-gray-500">
                                        {{ backup.size }}
                                    </td>
                                    <td class="p-4 text-gray-500">
                                        {{ backup.tables_count }}
                                    </td>
                                    <td class="p-4 text-gray-500">
                                        {{ formatDate(backup.created_at) }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <button @click="downloadBackup(backup)" class="text-blue-600 hover:text-blue-800" :title="$t('button.download')">
                                                <i class="fa fa-download"></i>
                                            </button>
                                            <button @click="restoreBackup(backup)" class="text-green-600 hover:text-green-800" :title="$t('button.restore')">
                                                <i class="fa fa-history"></i>
                                            </button>
                                            <button @click="deleteBackup(backup)" class="text-red-600 hover:text-red-800" :title="$t('button.delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!loading && backups.length === 0">
                                    <td colspan="5" class="p-4 text-center text-gray-500">
                                        {{ $t('message.no_data_found') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import axios from "axios";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "BackupComponent",
    data() {
        return {
            loading: false,
            backups: []
        }
    },
    mounted() {
        this.fetchBackups();
    },
    methods: {
        fetchBackups() {
            this.loading = true;
            axios.get('/admin/setting/system-backup').then(res => {
                this.backups = res.data.data;
                this.loading = false;
            }).catch(err => {
                this.loading = false;
                alertService.error(err.response.data.message);
            });
        },
        createBackup() {
            this.loading = true;
            axios.post('/admin/setting/system-backup').then(res => {
                this.loading = false;
                alertService.success(res.data.message);
                this.fetchBackups();
            }).catch(err => {
                this.loading = false;
                alertService.error(err.response.data.message);
            });
        },
        restoreBackup(backup) {
            appService.destroyConfirmation().then(() => {
                this.loading = true;
                axios.post('/admin/setting/system-backup/restore', { filename: backup.filename }).then(res => {
                    this.loading = false;
                    alertService.success(res.data.message || 'Backup restored successfully');
                }).catch(err => {
                    this.loading = false;
                    alertService.error(err.response?.data?.message || 'Restore failed');
                });
            }).catch(() => {
                // User cancelled
            });
        },
        deleteBackup(backup) {
            appService.destroyConfirmation().then(() => {
                this.loading = true;
                axios.delete(`/admin/setting/system-backup/${backup.filename}`).then(res => {
                    this.loading = false;
                    alertService.success(res.data.message || 'Backup deleted successfully');
                    this.fetchBackups();
                }).catch(err => {
                    this.loading = false;
                    alertService.error(err.response?.data?.message || 'Delete failed');
                });
            }).catch(() => {
                // User cancelled
            });
        },
        downloadBackup(backup) {
            axios.get(`/admin/setting/system-backup/download/${backup.filename}`, {
                responseType: 'blob'
            }).then(res => {
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', backup.filename);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
            }).catch(err => {
                alertService.error(err.response?.data?.message || 'Download failed');
            });
        },
        formatDate(date) {
            return moment(date).format('YYYY-MM-DD HH:mm:ss');
        }
    }
}
</script>
