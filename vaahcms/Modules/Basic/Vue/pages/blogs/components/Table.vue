<script setup>
import {vaah} from '../../../vaahvue/pinia/vaah'
import {useBlogsStore} from '../../../stores/store-blogs'

const store = useBlogsStore();
const useVaah = vaah();

</script>

<template>

    <div v-if="store.list">
        <!--table-->
        <DataTable :value="store.list.data"
                   dataKey="id"
                   class="p-datatable-sm p-datatable-hoverable-rows"
                   v-model:selection="store.action.items"
                   stripedRows
                   responsiveLayout="scroll">

            <Column selectionMode="multiple"
                    v-if="store.isViewLarge()"
                    headerStyle="width: 3em">
            </Column>

            <Column field="id" header="ID" :style="{width: store.getIdWidth()}" :sortable="true">
            </Column>

            <Column field="title" header="Title"
                    :sortable="true">
                <template #body="prop">
                    <Badge v-if="prop.data.deleted_at"
                           value="Trashed"
                           severity="danger"></Badge>
                    {{ prop.data.title }}
                </template>

            </Column>
            <Column header="Author">
                <template #body="{data}">
                    {{data.created_by_user.first_name}}
                </template>
            </Column>

            <Column field="updated_at" header="Updated"
                    v-if="store.isViewLarge()"
                    style="width:150px;"
                    :sortable="true">

                <template #body="prop">
                    {{ useVaah.toLocalTimeShortFormat(prop.data.updated_at) }}
                </template>

            </Column>
            <Column field="content" header="Content">
                <template #body="{data}">
                    <Button class="p-button-tiny p-button-text p-button-success"
                            data-testid="blogs-table-to-content-view"
                            v-tooltip.top="'Content'"
                            @click="store.toContent(data)"
                            icon="fa-regular fa-eye"/>
                </template>
            </Column>


            <Column field="actions" style="width:150px;"
                    :style="{width: store.getActionWidth() }"
                    :header="store.getActionLabel()">

                <template #body="prop">
                    <div class="p-inputgroup ">

                        <Button class="p-button-tiny p-button-text"
                                data-testid="blogs-table-to-view"
                                :disabled="store.item && store.item.id === prop.data.id"
                                v-tooltip.top="'View'"
                                @click="store.toView(prop.data)"
                                icon="pi pi-eye"/>

                        <Button class="p-button-tiny p-button-text"
                                data-testid="blogs-table-to-edit"
                                :disabled="store.item && store.item.id === prop.data.id"
                                v-tooltip.top="'Update'"
                                @click="store.toEdit(prop.data)"
                                icon="pi pi-pencil"/>

                        <Button class="p-button-tiny p-button-danger p-button-text"
                                data-testid="blogs-table-action-trash"
                                v-if="store.isViewLarge() && !prop.data.deleted_at"
                                @click="store.itemAction('trash', prop.data)"
                                v-tooltip.top="'Trash'"
                                icon="pi pi-trash"/>


                        <Button class="p-button-tiny p-button-success p-button-text"
                                data-testid="blogs-table-action-restore"
                                v-if="store.isViewLarge() && prop.data.deleted_at"
                                @click="store.itemAction('restore', prop.data)"
                                v-tooltip.top="'Restore'"
                                icon="pi pi-replay"/>


                    </div>

                </template>


            </Column>


        </DataTable>
        <!--/table-->

        <!--paginator-->
        <Paginator v-model:rows="store.query.rows"
                   :totalRecords="store.list.total"
                   :first="(store.query.page-1)*store.query.rows"
                   @page="store.paginate($event)"
                   :rowsPerPageOptions="store.rows_per_page"
                   class="bg-white-alpha-0 pt-2">
        </Paginator>
        <!--/paginator-->

    </div>
    <Dialog v-model:visible="store.content_dialog_visibility" header="Content" :style="{ width: '30rem' }"
            :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">

            <p class="m-0 text-xl font-semibold text-primary underline text-center uppercase">
           {{ store.content.title }}
        </p>
        <p class="text-base mt-3 text-justify line-height-2">
            {{ store.content.content }}
        </p>
    </Dialog>

</template>
