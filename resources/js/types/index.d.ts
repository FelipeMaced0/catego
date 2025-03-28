import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;


export interface CategoriaItem {
    id?: number;
    pai_id?: number;
    nome: string;
    descricao: string;
    sub_categorias?: CategoriaItem[]
    created_at?: string;
    updated_at?: string;
}

export type ItemCategoriaType = CategoriaItem;

export interface Paginacao<T> {
    current_page: number,
    data: Array<T>,
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    links: Array<any>,
    next_page_url?: string,
    path:string,
    per_page: number,
    prev_page_url?: string,
    to: number,
    total: number
}

export type PaginacaoType = Paginacao;