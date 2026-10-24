import { Head, Link } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { show as orderShow } from '@/routes/orders';
import type { Order, OrderStatus } from '@/types';

const statusVariant: Record<OrderStatus, 'default' | 'secondary' | 'destructive' | 'outline'> = {
    pending: 'outline',
    paid: 'default',
    fulfilled: 'default',
    failed: 'destructive',
    expired: 'destructive',
    cancelled: 'secondary',
};

export default function OrdersIndex({ orders }: { orders: Order[] }) {
    return (
        <>
            <Head title="My orders" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <h1 className="text-xl font-semibold">My orders</h1>

                {orders.length === 0 ? (
                    <p className="text-muted-foreground">You haven't placed any orders yet.</p>
                ) : (
                    <div className="space-y-3">
                        {orders.map((order) => (
                            <Link key={order.order_number} href={orderShow(order.order_number)}>
                                <Card className="hover:bg-accent/50">
                                    <CardContent className="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <p className="font-medium">{order.order_number}</p>
                                            <p className="text-sm text-muted-foreground">
                                                {new Date(order.placed_at).toLocaleString()}
                                            </p>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <span className="font-medium">
                                                ₱{order.total.toFixed(2)}
                                            </span>
                                            <Badge variant={statusVariant[order.status]}>
                                                {order.status}
                                            </Badge>
                                        </div>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

OrdersIndex.layout = {
    breadcrumbs: [{ title: 'My orders', href: '/orders' }],
};
