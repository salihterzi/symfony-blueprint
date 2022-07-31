export interface User {
    id?: string;
    email?: string;
    firstName?: string;
    lastName?: string;
    permissions:{[name: string]: boolean|number[]};
}

