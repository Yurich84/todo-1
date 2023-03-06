import axios from 'axios'

const API_ENDPOINT = 'todos'

export default {

    all(data) {
        return axios.get(API_ENDPOINT, {params: data})
    },

    find(id) {
        return axios.get(API_ENDPOINT + '/' + id)
    },

    done(id) {
        return axios.get(API_ENDPOINT + '/done/' + id)
    },

    create(model) {
        return axios.post(API_ENDPOINT, model)
    },

    update(model) {
        return axios.put(API_ENDPOINT + '/' + model.id, model)
    },

    delete(id) {
        return  axios.delete(API_ENDPOINT + '/' + id)
    },
}
