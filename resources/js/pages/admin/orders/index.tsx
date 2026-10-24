import { Head } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import type { Order } from '@/types';

export default function AdminOrdersIndex({ orders }: { orders: Order[] }) {
    return (
        <>
            <Head title="Admin · Orders" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div>
                    <h1 className="text-xl font-semibold">Orders</h1>
                    <p className="text-sm text-muted-foreground">
                        Orders placed in the current session. This list will show every order once
                        it's backed by a real database table.
                    </p>
                </div>

                <div className="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3">Order #</th>
                                <th className="p-3">Placed</th>
                                <th className="p-3">Total</th>
                                <th className="p-3">Status</th>
                                <th className="p-3">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            {orders.map((order) => (
                                <tr
                                    key={order.order_number}
                                    className="border-t border-sidebar-border/70 dark:border-sidebar-border"
                                >
                                    <td className="p-3 font-medium">{order.order_number}</td>
                                    <td className="p-3">{new Date(order.placed_at).toLocaleString()}</td>
                                    <td className="p-3">₱{order.total.toFixed(2)}</td>
                                    <td className="p-3">
                                        <Badge variant="outline">{order.status}</Badge>
                                    </td>
                                    <td className="p-3">{order.payment.status}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

AdminOrdersIndex.layout = {
    breadcrumbs: [{ title: 'Admin · Orders', href: '/admin/orders' }],
};
