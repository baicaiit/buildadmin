import createAxios from '/@/utils/axios'
const controllerUrl = '/index.php/admin/auth.menu/'
export const actionUrl = new Map([
    ['index', controllerUrl + 'index'],
    ['add', controllerUrl + 'add'],
    ['edit', controllerUrl + 'edit'],
    ['del', controllerUrl + 'del'],
    ['sortable', controllerUrl + 'sortable'],
])

export function index(loading: boolean, keyword: string = '') {
    return createAxios(
        {
            url: actionUrl.get('index'),
            method: 'get',
            params: keyword ? { keyword: keyword } : {},
        },
        {
            loading: loading,
        }
    )
}

export function edit(params: anyObj) {
    return createAxios({
        url: actionUrl.get('edit'),
        method: 'get',
        params: params,
    })
}

export function del(ids: string[]) {
    return createAxios(
        {
            url: actionUrl.get('del'),
            method: 'DELETE',
            data: {
                ids: ids,
            },
        },
        {
            showSuccessMessage: true,
        }
    )
}

export function postData(action: string, data: anyObj) {
    if (!actionUrl.has(action)) {
        throw new Error('action 不存在！')
    }
    return createAxios(
        {
            url: actionUrl.get(action),
            method: 'post',
            data: data,
        },
        {
            showSuccessMessage: true,
        }
    )
}

export function sortableApi(id: number, targetId: number) {
    return createAxios(
        {
            url: actionUrl.get('sortable'),
            method: 'post',
            data: {
                id: id,
                targetId: targetId,
            },
        },
        {
            showSuccessMessage: true,
        }
    )
}