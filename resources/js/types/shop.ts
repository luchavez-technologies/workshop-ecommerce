export type Category = {
    id: number;
    name: string;
    slug: string;
};

export type Product = {
    id: number;
    category_id: number;
    category: Category | null;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock_quantity: number;
    image_path: string | null;
    is_active: boolean;
};

export type CartItem = {
    product_id: number;
    product: Product;
    quantity: number;
    unit_price: number;
    line_total: number;
};

export type Totals = {
    subtotal: number;
    shipping_fee: number;
    tax: number;
    discount: number;
    total: number;
};

export type OrderItem = {
    product_id: number;
    product_name: string;
    unit_price: number;
    quantity: number;
    line_total: number;
};

export type PaymentStatus = 'pending' | 'success' | 'failed' | 'expired';

export type Payment = {
    provider: string;
    checkout_id: string | null;
    request_reference_number: string | null;
    redirect_url: string | null;
    status: PaymentStatus;
    currency: string;
    amount: number;
    paid_at: string | null;
};

export type OrderStatus =
    | 'pending'
    | 'paid'
    | 'failed'
    | 'expired'
    | 'fulfilled'
    | 'cancelled';

export type Order = {
    id: number;
    order_number: string;
    user_id: number;
    status: OrderStatus;
    items: OrderItem[];
    subtotal: number;
    discount: number;
    shipping_fee: number;
    tax: number;
    total: number;
    currency: string;
    placed_at: string;
    paid_at: string | null;
    cancelled_at: string | null;
    payment: Payment;
};
