import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { index as ordersIndex } from '@/routes/orders';
import { index as shopIndex } from '@/routes/shop';
import type { Order } from '@/types';

export default function CheckoutSuccess({ order }: { order: Order | null }) {
    return (
        <>
            <Head title="Payment successful" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <Card className="max-w-lg">
                    <CardContent className="space-y-4 py-8">
                        <h1 className="text-xl font-semibold">Thank you for your order!</h1>

                        {order ? (
                            <p className="text-muted-foreground">
                                Order <span className="font-medium">{order.order_number}</span> for ₱
                                {order.total.toFixed(2)} is being processed.
                            </p>
                        ) : (
                            <p className="text-muted-foreground">
                                Your payment was received, but we couldn't find the matching order.
                            </p>
                        )}

                        <div className="flex gap-3">
                            <Button asChild>
                                <Link href={ordersIndex()}>View my orders</Link>
                            </Button>
                            <Button asChild variant="outline">
                                <Link href={shopIndex()}>Continue shopping</Link>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

CheckoutSuccess.layout = {
    breadcrumbs: [{ title: 'Checkout', href: '/checkout' }],
};
