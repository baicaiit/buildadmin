import createAxios from '/@/utils/axios'

export function dashboard() {
    return createAxios({
        url: '/Dashboard/dashboard',
        method: 'get',
    })
}
