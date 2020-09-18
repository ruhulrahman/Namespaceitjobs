export default class Gate {
    constructor(user) {
        this.user = user
    }

    isAdmin() {
        return this.user.type === 'admin'
    }

    isUser() {
        return this.user.type === 'user'
    }

    isAuthor() {
        return this.user.type === 'author'
    }

    isSuper() {
        return this.user.type === 'super'
    }

    isAdminOrAuthorOrSuper() {
        if (this.user.type === 'super' || this.user.type === 'admin' || this.user.type === 'author') {
            return true;
        }
    }

    isAuthorOrUser() {
        if (this.user.type === 'author' || this.user.type === 'author') {
            return true;
        }
    }

}