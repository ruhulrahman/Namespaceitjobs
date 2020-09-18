export default class Gate {
    constructor(user) {
        this.user = user
    }

    isEmployee() {
        return this.user.type === 'employee'
    }

    isEmployer() {
        return this.user.type === 'employer'
    }

    isAdmin() {
        return this.user.type === 'admin'
    }


}